package org.frieder.aoc.day6.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static java.util.stream.Collectors.collectingAndThen;

public class Solution6A {
    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");

    public static double getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> PATTERN_NUMBERS.matcher(ln)
                        .results()
                        .map(mr -> Integer.parseInt(mr.group(1)))
                        .collect(Collectors.toList()))
                .collect(collectingAndThen(
                        Collectors.toList(),
                        d -> IntStream.range(0, d.get(0).size())
                                .mapToObj(i -> new Race(d.get(0).get(i), d.get(1).get(i)))
                                .collect(Collectors.toList())
                ))
                .stream()
                .map(r -> IntStream.range(0, r.getTime() + 1)
                        .map(i -> (i * (r.getTime() - i) > r.getDistance()) ? 1 : 0)
                        .sum())
                .reduce(1, (a, b) -> a * b);
    }
}