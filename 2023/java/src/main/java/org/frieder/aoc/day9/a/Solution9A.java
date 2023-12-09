package org.frieder.aoc.day9.a;

import org.frieder.aoc.day8.a.Direction;

import java.io.IOException;
import java.math.BigInteger;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.Map;
import java.util.Objects;
import java.util.concurrent.atomic.AtomicReference;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.stream.DoubleStream;

import static java.util.stream.Collectors.teeing;
import static java.util.stream.Collectors.toList;

public class Solution9A {

    private static final Pattern PATTERN_NUMBERS = Pattern.compile("(-?[0-9]+)");

    public static double getResult(String path) throws IOException {
        return new Solution9A().getNonStaticResult(path);
    }

    private double getNonStaticResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> PATTERN_NUMBERS.matcher(ln)
                        .results()
                        .map(mr -> Double.parseDouble(mr.group(1)))
                        .collect(toList())
                )
                .map(this::getNextNumber)
                .mapToDouble(d -> d)
                .sum();
    }

    private double getNextNumber(List<Double> l) {

        if (l.stream().allMatch(n -> n == 0)) {
            return 0D;
        }

        AtomicReference<Double> lastNumber = new AtomicReference<>(null);

        List<Double> nextHistory = l.stream()
                .map(n -> {
                    if (lastNumber.get() == null) {
                        lastNumber.set(n);
                        return null;
                    }

                    return n - lastNumber.getAndSet(n);
                })
                .filter(Objects::nonNull)
                .collect(toList());

        return l.get(l.size() - 1) + this.getNextNumber(nextHistory);
    }
}