package org.frieder.aoc.day8.b;

import org.frieder.aoc.day8.a.Direction;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.Map;
import java.util.concurrent.atomic.AtomicReference;
import java.util.stream.Collectors;

import static java.lang.Math.abs;
import static java.lang.Math.max;
import static java.lang.Math.min;
import static java.util.stream.Collectors.teeing;
import static java.util.stream.Collectors.toList;

public class Solution8B {

    public static double getResult(String path) throws IOException {
        return new Solution8B().getNonStaticResult(path);
    }

    private Double getNonStaticResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .collect(teeing(
                                Collectors.filtering(ln -> !ln.contains("="), toList()),
                                Collectors.filtering(ln -> ln.contains("="), toList()),
                                (d, n) -> getWayLength(d, n.stream()
                                        .map(ln -> ln.replaceAll("[,()]|= ", "").split(" "))
                                        .collect(Collectors.toMap(ln -> ((String[]) ln)[0], ln -> new Direction(
                                                ((String[]) ln)[1],
                                                ((String[]) ln)[2]
                                        ))))
                        )
                );
    }

    private Double getWayLength(List<String> d, Map<String, Direction> net) {
        return net.keySet()
                .stream()
                .filter(direction -> direction.charAt(2) == 'A')
                .map(direction -> this.findPath(direction, 0, d, net))
                .reduce(1D, this::getKgv);
    }

    private double findPath(String initialNetName, double initialWayLength, List<String> d, Map<String, Direction> net) {
        AtomicReference<String> curNetName = new AtomicReference<>(initialNetName);
        AtomicReference<Double> wayLength = new AtomicReference<>(initialWayLength);
        return d.get(0).chars()
                .mapToObj(i -> (char) i)
                .map(i -> {
                    String nextNetName = net.get(curNetName.get()).getByDirectionChar(i);
                    curNetName.set(nextNetName);
                    wayLength.getAndUpdate(v -> v + 1);
                    return nextNetName.charAt(2) == 'Z';
                })
                .filter(b -> b)
                .findFirst()
                .orElse(false) ?
                wayLength.get() :
                findPath(curNetName.get(), wayLength.get(), d, net);
    }

    private double getKgv(double a, double b) {
        double v1 = max(a, b);
        double v2 = min(a, b);
        double ggv = 1;
        while (v2 != 0) {
            ggv = v2;
            v2 = v1 % v2;
            v1 = ggv;
        }
        return abs(a * b) / ggv;
    }

}