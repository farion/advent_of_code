package org.frieder.aoc.day10.a;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicReference;
import java.util.regex.Pattern;
import java.util.stream.Stream;

import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;

public class Solution10A {

    private final Logger LOGGER = LoggerFactory.getLogger(Solution10A.class);
    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([FJL7S\\-|.])");
    private Map<Coordinate, Field> inputMap;

    public static double getResult(String path) throws IOException {
        return new Solution10A().getNonStaticResult(path);
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

        this.printMap();

        Coordinate last = null;
        Coordinate cur = start.get();
        int count = 0;
        do {
            Coordinate newLast = cur;
            cur = this.findNextStep(cur, last);
            last = newLast;
            count++;
        } while (!start.get().equals(cur));

        return ((double) count) / 2;
    }

    private void printMap() {

        AtomicInteger x = new AtomicInteger();
        AtomicInteger y = new AtomicInteger();
        inputMap.forEach((key, value) -> {
            x.set(Math.max(x.get(), key.getX()));
            y.set(Math.max(y.get(), key.getY()));
        });

        for (int i = 0; i <= x.get(); i++) {
            StringBuilder sb = new StringBuilder();
            for (int j = 0; j <= y.get(); j++) {
                sb.append(inputMap.get(new Coordinate(j, i)).getPipe().getStr());
            }
            LOGGER.debug(sb.toString());
        }

    }

    private Coordinate findNextStep(Coordinate c, Coordinate last) {

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
                List<Coordinate> connects = Stream.of(up, down, left, right)
                        .filter(coord -> coord != null && !coord.equals(c) && inputMap.get(coord).getPipe() != Pipe.__)
                        .collect(toList());

                a = connects.get(0);
                b = last;

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

}