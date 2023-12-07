package org.frieder.aoc.day7.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class Solution7B {
    private static final Pattern PATTERN_CARDS = Pattern.compile("([A-Z0-9]+) ([0-9]+)");

    public static double getResult(String path) throws IOException {

        AtomicInteger rank = new AtomicInteger();

        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> PATTERN_CARDS.matcher(ln)
                        .results()
                        .map(mr -> new Card(
                                mr.group(1),
                                Integer.parseInt(mr.group(2)))
                        )
                        .collect(Collectors.toList())
                )
                .flatMap(Collection::stream)
                .sorted(Card::compareTo)
                .map(c -> c.getBid() * rank.incrementAndGet())
                .mapToInt(Integer::intValue)
                .sum();
    }

}