package org.frieder.aoc.day10.b;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicReference;
import java.util.regex.Pattern;
import java.util.stream.IntStream;

import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;
import static org.frieder.aoc.day10.b.Pipe.I;
import static org.frieder.aoc.day10.b.Pipe.O;
import static org.frieder.aoc.day10.b.Pipe.__;

public class Solution10B {

    private final Logger LOGGER = LoggerFactory.getLogger(Solution10B.class);

    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([FJL7S\\-|.])");
    private Map<Coordinate, Field> inputMap;

    private final Map<Coordinate, Field> solutionMap = new HashMap<>();
    private int maxY;
    private int maxX;

    public static double getResult(String path) throws IOException {
        return new Solution10B().getNonStaticResult(path);
    }

    private double getNonStaticResult(String path) throws IOException {
        AtomicInteger x = new AtomicInteger();
        AtomicInteger y = new AtomicInteger();
        AtomicReference<Coordinate> start = new AtomicReference<>();
        this.inputMap = Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> {
                            int curY = y.getAndIncrement();
                            x.set(0);
                            return PATTERN_NUMBERS.matcher(ln)
                                    .results()
                                    .map(mr -> {
                                                Pipe pipe = Pipe.byStr(mr.group(1));
                                                Coordinate coordinate = new Coordinate(x.getAndIncrement(), curY);
                                                if (pipe == Pipe.S) {
                                                    start.set(coordinate);
                                                }
                                                return new Field(coordinate, pipe);
                                            }
                                    )
                                    .collect(toList());
                        }
                )
                .flatMap(Collection::stream)
                .collect(toMap(Field::getCoordinate, f -> f));

        maxX = x.get();
        maxY = y.get();

        fillEmptySolutionMap();

        this.printMap(inputMap);

        Coordinate last = null;
        Coordinate cur = start.get();
        do {
            Coordinate newLast = cur;
            cur = this.findNextStep(cur, last);
            last = newLast;
        } while (!start.get().equals(cur));


        // mark known outside points
        markAnOutsidePointTouchingPipe();
        markBorderPointsAsOutside();


        this.printMap(solutionMap);

        // do the real stuff
        markRemainingPoints();

        //$this->print_map($this->inputMap);
        //$this->print_map($this->solutionMap);

        // count inside
        return countInsidePoints();
    }

    private double countInsidePoints() {
        return solutionMap.values()
                .stream()
                .filter(f -> f.getPipe().equals(I))
                .count();
    }

    private void markRemainingPoints() {
        solutionMap.values()
                .forEach(f -> {
                    if (f.getPipe().equals(__)) {
                        markRemainingPoint(f.getCoordinate());
                    }
                });

    }

    private void markRemainingPoint(Coordinate coordinate) {

        LOGGER.debug("Start " + coordinate);

        Set<Coordinate> checked = new HashSet<>();
        checked.add(coordinate);

        Coordinate pipeC = findPipe(coordinate, checked);

        Pipe r;
        if (pipeC != null) {

            Coordinate prev = null;
            Coordinate cur = pipeC;
            AtomicReference<Pipe> pipe = new AtomicReference<>();
            do {
                Coordinate newPrev = cur;
                cur = traversePipe(prev, cur, pipeC, checked, pipe);
                prev = newPrev;
            } while (cur != null);

            r = pipe.get();
        } else {
            r = O;
        }

        checked.forEach(c -> {
            solutionMap.get(c).setPipe(r);
        });

        LOGGER.debug("-----");
    }

    private Coordinate traversePipe(Coordinate prev, Coordinate coordinate, Coordinate start, Set<Coordinate> checked, AtomicReference<Pipe> result) {
        // We traverse always left or top
        Pipe symbol = solutionMap.get(coordinate).getPipe();

        Set<Coordinate> check = new HashSet<>();

        Coordinate next;

        // Startpoint with no direction yet
        if (prev == null) {
            switch (symbol) {
                case DR:
                    next = coordinate.right();
                    break;
                case UR:
                    next = coordinate.up();
                    break;
                case UL:
                    next = coordinate.up();
                    break;
                case DL:
                    next = coordinate.left();
                    break;
                case LR:
                    next = coordinate.right();
                    break;
                case DU:
                    next = coordinate.up();
                    break;
                case O:
                    LOGGER.debug("Traverse outside(2) -> I");
                    result.set(O);
                    return null;
                case I:
                    LOGGER.debug("Traverse outside(2) -> I");
                    result.set(I);
                    return null;
                default:
                    throw new RuntimeException();
            }

            // get next traversing step and traversed points
        } else {

            // We're back without hitting an O -> we are inside.
            if (coordinate.equals(start)) {
                LOGGER.debug("Traverse loop " + coordinate + " -> I");
                result.set(I);
                return null;
            }

            // get direction from the entrance coordinate
            Direction d = this.getDirection(prev, coordinate);
            LOGGER.debug("Traverse (" + symbol.getStr() + ") " + d + " " + prev + " -> " + coordinate);

            switch (symbol) {
                case DR:
                    if (d == Direction.LEFT) {
                        next = coordinate.down();
                        // no check
                    } else if (d == Direction.UP) {
                        next = coordinate.right();
                        check.add(coordinate.left());
                        check.add(coordinate.up());
                        check.add(new Coordinate(coordinate.getX() - 1, coordinate.getY() - 1));
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case UR:
                    if (d == Direction.DOWN) {
                        next = coordinate.right();
                        // no check
                    } else if (d == Direction.LEFT) {
                        next = coordinate.up();
                        check.add(coordinate.left());
                        check.add(coordinate.down());
                        check.add(new Coordinate(coordinate.getX() - 1, coordinate.getY() + 1));
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case DL:
                    if (d == Direction.RIGHT) {
                        next = coordinate.down();
                        check.add(coordinate.right());
                        check.add(coordinate.up());
                        check.add(new Coordinate(coordinate.getX() + 1, coordinate.getY() - 1));
                    } else if (d == Direction.UP) {
                        next = coordinate.left();
                        // no check
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case UL:
                    if (d == Direction.DOWN) {
                        next = coordinate.left();
                        check.add(coordinate.right());
                        check.add(coordinate.down());
                        check.add(new Coordinate(coordinate.getX() + 1, coordinate.getY() + 1));
                    } else if (d == Direction.RIGHT) {
                        next = coordinate.up();
                        // no check
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case LR:
                    if (d == Direction.RIGHT) {
                        next = coordinate.right();
                        check.add(coordinate.up());
                    } else if (d == Direction.LEFT) {
                        next = coordinate.left();
                        check.add(coordinate.down());
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case DU:
                    if (d == Direction.DOWN) {
                        next = coordinate.down();
                        check.add(coordinate.right());
                    } else if (d == Direction.UP) {
                        next = coordinate.up();
                        check.add(coordinate.left());
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                case O:
                    LOGGER.debug("Traverse outside(1) -> O");
                    result.set(O);
                    return null;
                case I:
                    LOGGER.debug("Traverse outside(1) -> I");
                    result.set(I);
                    return null;
                default:
                    throw new RuntimeException();
            }
        }

        // add new points to the lot of traversed ones
        check.stream()
                .filter(c -> solutionMap.get(c) != null && solutionMap.get(c).getPipe() == __)
                .forEach(checked::add);

        // check if we hit an already decided point
        Coordinate c1 = check.stream()
                .filter(c -> solutionMap.get(c) != null && solutionMap.get(c).getPipe() == O)
                .findFirst()
                .orElse(null);

        if (c1 != null) {
            solutionMap.get(c1).setPipe(O);
            result.set(O);
            return null;
        }

        Coordinate c2 = check.stream()
                .filter(c -> solutionMap.get(c) != null && solutionMap.get(c).getPipe() == I)
                .findFirst()
                .orElse(null);

        if (c2 != null) {
            solutionMap.get(c2).setPipe(I);
            result.set(I);
            return null;
        }

        // go on
        return next; //traversePipe(coordinate, next, start, checked);
    }

    private Direction getDirection(Coordinate prev, Coordinate coordinate) {
        if (prev.up().equals(coordinate)) {
            return Direction.UP;
        }
        if (prev.down().equals(coordinate)) {
            return Direction.DOWN;
        }
        if (prev.right().equals(coordinate)) {
            return Direction.RIGHT;
        }
        if (prev.left().equals(coordinate)) {
            return Direction.LEFT;
        }
        throw new RuntimeException();
    }

    private Coordinate findPipe(Coordinate coordinate, Set<Coordinate> checked) {
        Coordinate next = coordinate;

        while (solutionMap.get(next) != null && this.solutionMap.get(next).getPipe() == __) {
            checked.add(next);
            next = next.right();
        }

        if (solutionMap.get(next) == null) {
            return null;
        }
        if (this.solutionMap.get(next).getPipe() != __) {
            LOGGER.debug("Found pipe at " + next);
            return next;
        }

        return findPipe(coordinate.down(), checked);
    }

    private void markBorderPointsAsOutside() {

        IntStream.range(0, maxX).forEach(
                x -> {
                    Field f1 = solutionMap.get(new Coordinate(x, 0));
                    if (f1 != null && f1.getPipe().equals(__)) {
                        f1.setPipe(Pipe.O);
                    }
                    Field f2 = solutionMap.get(new Coordinate(x, maxY - 1));
                    if (f2 != null && f2.getPipe().equals(__)) {
                        f2.setPipe(Pipe.O);
                    }
                }
        );


        IntStream.range(0, maxY).forEach(
                y -> {
                    Field f1 = solutionMap.get(new Coordinate(0, y));
                    if (f1 != null && f1.getPipe().equals(__)) {
                        f1.setPipe(Pipe.O);
                    }
                    Field f2 = solutionMap.get(new Coordinate(maxX - 1, y));
                    if (f2 != null && f2.getPipe().equals(__)) {
                        f2.setPipe(Pipe.O);
                    }
                }
        );

    }

    private void markAnOutsidePointTouchingPipe() {

    }

    private void fillEmptySolutionMap() {
        for (int i = 0; i < maxY; i++) {
            for (int j = 0; j < maxX; j++) {
                Coordinate c = new Coordinate(j, i);
                solutionMap.put(c, new Field(c, __));
            }
        }
    }

    private void printMap(Map<Coordinate, Field> m) {


        for (int i = 0; i < maxY; i++) {
            StringBuilder sb = new StringBuilder();
            for (int j = 0; j < maxX; j++) {
                sb.append(m.get(new Coordinate(j, i)).getPipe().getStr());
            }
            LOGGER.debug(sb.toString());
        }
        LOGGER.debug("-----");

    }

    private Coordinate findNextStep(Coordinate c, Coordinate last) {

        try {
            solutionMap.get(c).setPipe(inputMap.get(c).getPipe());
        } catch (Exception e) {
            throw new RuntimeException();
        }

        Coordinate up = inputMap.getOrDefault(c.up(), new Field(null, null)).getCoordinate();
        Coordinate down = inputMap.getOrDefault(c.down(), new Field(null, null)).getCoordinate();
        Coordinate left = inputMap.getOrDefault(c.left(), new Field(null, null)).getCoordinate();
        Coordinate right = inputMap.getOrDefault(c.right(), new Field(null, null)).getCoordinate();

        Pipe curPipe = inputMap.get(c).getPipe();

        Coordinate a;
        Coordinate b;

        switch (curPipe) {

            case DR:
                a = right;
                b = down;
                break;
            case DL:
                a = left;
                b = down;
                break;
            case UR:
                a = up;
                b = right;
                break;
            case UL:
                a = up;
                b = left;
                break;
            case DU:
                a = up;
                b = down;
                break;
            case LR:
                a = left;
                b = right;
                break;
            case S:

                // replace S with correct symbol
                if ((containsPipe(right, Pipe.DL) || containsPipe(right, Pipe.UL) || containsPipe(right, Pipe.LR)) &&
                        (containsPipe(left, Pipe.UR) || containsPipe(left, Pipe.DR) || containsPipe(left, Pipe.LR))) {
                    solutionMap.get(c).setPipe(Pipe.LR);
                    a = c.right();
                    b = c.left();
                } else if ((containsPipe(up, Pipe.DL) || containsPipe(up, Pipe.DR) || containsPipe(up, Pipe.DU)) &&
                        (containsPipe(down, Pipe.UR) || containsPipe(down, Pipe.UL) || containsPipe(down, Pipe.DU))) {
                    solutionMap.get(c).setPipe(Pipe.DU);
                    a = c.down();
                    b = c.up();
                } else if ((containsPipe(right, Pipe.DL) || containsPipe(right, Pipe.UL) || containsPipe(right, Pipe.LR)) &&
                        (containsPipe(down, Pipe.UR) || containsPipe(down, Pipe.UL) || containsPipe(down, Pipe.DU))) {
                    solutionMap.get(c).setPipe(Pipe.DR);
                    a = c.down();
                    b = c.right();
                } else if ((containsPipe(right, Pipe.DL) || containsPipe(right, Pipe.UL) || containsPipe(right, Pipe.LR)) &&
                        (containsPipe(up, Pipe.DL) || containsPipe(up, Pipe.DR) || containsPipe(up, Pipe.DU))) {
                    solutionMap.get(c).setPipe(Pipe.UR);
                    a = c.up();
                    b = c.right();
                } else if ((containsPipe(up, Pipe.DL) || containsPipe(up, Pipe.DR) || containsPipe(up, Pipe.DU)) &&
                        (containsPipe(left, Pipe.UR) || containsPipe(left, Pipe.DR) || containsPipe(left, Pipe.LR))) {
                    solutionMap.get(c).setPipe(Pipe.UL);
                    a = c.up();
                    b = c.left();
                } else if ((containsPipe(down, Pipe.UR) || containsPipe(down, Pipe.UL) || containsPipe(down, Pipe.DU)) &&
                        (containsPipe(left, Pipe.UR) || containsPipe(left, Pipe.DR) || containsPipe(left, Pipe.LR))) {
                    solutionMap.get(c).setPipe(Pipe.DL);
                    a = c.down();
                    b = c.left();
                } else {
                    throw new RuntimeException();
                }

                break;
            default:
                throw new RuntimeException();
        }

        Coordinate next;

        if (last != null && last.equals(a)) {
            next = b;
        } else if (last != null && last.equals(b)) {
            next = a;
        } else if (last == null) {
            next = a;
        } else {
            throw new RuntimeException();
        }

        return next;
    }

    private boolean containsPipe(Coordinate c, Pipe pipe) {
        Field f = inputMap.get(c);
        return f != null && pipe.equals(f.getPipe());
    }

}