package org.frieder.aoc.day8.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.Map;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicReference;
import java.util.stream.Collectors;

import static java.util.stream.Collectors.teeing;
import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;

public class Solution8A {

    public static double getResult(String path) throws IOException {
        return new Solution8A().getNonStaticResult(path);
    }

    private Double getNonStaticResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .collect(teeing(
                                Collectors.filtering(ln -> !ln.contains("="), toList()),
                                Collectors.filtering(ln -> ln.contains("="), toList()),
                                (d, n) -> this.getWayLength("AAA", 0, d, n.stream()
                                        .map(ln -> ln.replaceAll("[,()]|= ", "").split(" "))
                                        .collect(Collectors.toMap(ln -> ((String[]) ln)[0], ln -> new Direction(
                                                ((String[]) ln)[1],
                                                ((String[]) ln)[2]
                                        ), (a, b) -> a)))
                        )
                );
    }

    private double getWayLength(String initialNetName, double initialWayLength, List<String> d, Map<String, Direction> net) {
        AtomicReference<String> curNetName = new AtomicReference<>(initialNetName);
        AtomicReference<Double> wayLength = new AtomicReference<>(initialWayLength);
        return d.get(0).chars()
                .mapToObj(i -> (char) i)
                .map(i -> {
                    String nextNetName = net.get(curNetName.get()).getByDirectionChar(i);
                    curNetName.set(nextNetName);
                    wayLength.getAndUpdate(v -> v + 1);
                    return nextNetName.equals("ZZZ");
                })
                .filter(b -> b)
                .findFirst()
                .orElse(false) ?
                wayLength.get() :
                getWayLength(curNetName.get(), wayLength.get(), d, net);
    }
}