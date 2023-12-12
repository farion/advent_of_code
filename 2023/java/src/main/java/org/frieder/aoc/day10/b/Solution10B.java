package org.frieder.aoc.day10.b;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicReference;
import java.util.function.Function;
import java.util.regex.Pattern;
import java.util.stream.IntStream;

import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;
import static org.frieder.aoc.day10.b.Direction.DOWN;
import static org.frieder.aoc.day10.b.Direction.LEFT;
import static org.frieder.aoc.day10.b.Direction.RIGHT;
import static org.frieder.aoc.day10.b.Direction.UNDEFINED;
import static org.frieder.aoc.day10.b.Direction.UP;
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
        markBorderPointsAsOutside();

        this.printMap(solutionMap);

        // do the real stuff
        markRemainingPoints();

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

        checked.forEach(c -> solutionMap.get(c).setPipe(r));

        LOGGER.debug("-----");
    }

    private Coordinate traversePipe(Coordinate prev, Coordinate c, Coordinate start, Set<Coordinate> checked, AtomicReference<Pipe> result) {
        // We traverse always left or top
        Pipe symbol = solutionMap.get(c).getPipe();

        Set<Coordinate> checks = new HashSet<>();

        Coordinate next;

        // Startpoint with no direction yet
        if (prev == null) {
            switch (symbol) {
                case DR:
                case LR:
                    next = c.right();
                    break;
                case UR:
                case DU:
                case UL:
                    next = c.up();
                    break;
                case DL:
                    next = c.left();
                    break;
                case O:
                case I:
                    LOGGER.debug("Traverse in/outside -> " + symbol);
                    result.set(symbol);
                    return null;
                default:
                    throw new RuntimeException();
            }

            // get next traversing step and traversed points
        } else {

            // We're back without hitting an O -> we are inside.
            if (c.equals(start)) {
                LOGGER.debug("Traverse loop " + c + " -> I");
                result.set(I);
                return null;
            }

            // get direction from the entrance coordinate
            Direction d = this.getDirection(prev, c);
            LOGGER.debug("Traverse (" + symbol.getStr() + ") " + d + " " + prev + " -> " + c);

            switch (symbol.name() + d.name()) {
                case "DRLEFT":
                    next = c.down();
                    break;
                case "DRUP":
                    next = c.right();
                    checks.addAll(List.of(c.left(), c.up(), c.move(-1, -1)));
                    break;
                case "URDOWN":
                    next = c.right();
                    break;
                case "URLEFT":
                    next = c.up();
                    checks.addAll(List.of(c.left(), c.down(), new Coordinate(c.getX() - 1, c.getY() + 1)));
                    break;
                case "DLRIGHT":
                    next = c.down();
                    checks.addAll(List.of(c.right(), c.up(), c.move(1, -1)));
                    break;
                case "DLUP":
                    next = c.left();
                    break;
                case "ULDOWN":
                    next = c.left();
                    checks.addAll(List.of(c.right(), c.down(), c.move(1, 1)));
                    break;
                case "ULRIGHT":
                    next = c.up();
                    break;
                case "LRRIGHT":
                    next = c.right();
                    checks.add(c.up());
                    break;
                case "LRLEFT":
                    next = c.left();
                    checks.add(c.down());
                    break;
                case "DUDOWN":
                    next = c.down();
                    checks.add(c.right());
                    break;
                case "DUUP":
                    next = c.up();
                    checks.add(c.left());
                    break;
                case "OLEFT":
                case "ORIGHT":
                case "OUP":
                case "ODOWN":
                    LOGGER.debug("Traverse outside(1) -> O");
                    result.set(O);
                    return null;
                case "ILEFT":
                case "IRIGHT":
                case "IUP":
                case "IDOWN":
                    LOGGER.debug("Traverse outside(1) -> I");
                    result.set(I);
                    return null;
                default:
                    throw new RuntimeException();
            }
        }

        // add new points to the lot of traversed ones
        checks.stream()
                .filter(check -> solutionMap.get(check) != null && solutionMap.get(check).getPipe() == __)
                .forEach(checked::add);

        // check if we hit an already decided point
        Coordinate c1 = checks.stream()
                .filter(check -> solutionMap.get(check) != null && solutionMap.get(check).getPipe() == O)
                .findFirst()
                .orElse(null);

        if (c1 != null) {
            solutionMap.get(c1).setPipe(O);
            result.set(O);
            return null;
        }

        Coordinate c2 = checks.stream()
                .filter(check -> solutionMap.get(check) != null && solutionMap.get(check).getPipe() == I)
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
        if (prev == null) return UNDEFINED;
        if (prev.up().equals(coordinate)) return UP;
        if (prev.down().equals(coordinate)) return DOWN;
        if (prev.right().equals(coordinate)) return RIGHT;
        if (prev.left().equals(coordinate)) return LEFT;
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
        markBorderPointsAsOutside(maxX, List.of(x -> new Coordinate(x, 0), x -> new Coordinate(x, maxY - 1)));
        markBorderPointsAsOutside(maxY, List.of(y -> new Coordinate(0, y), y -> new Coordinate(maxX - 1, y)));
    }

    private void markBorderPointsAsOutside(int max, List<Function<Integer, Coordinate>> fns) {
        IntStream.range(0, max).forEach(
                i -> fns.forEach(fn -> {
                    Field f1 = solutionMap.get(fn.apply(i));
                    if (f1 != null && f1.getPipe().equals(__)) {
                        f1.setPipe(Pipe.O);
                    }
                })
        );
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
        Direction d = getDirection(last, c);

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