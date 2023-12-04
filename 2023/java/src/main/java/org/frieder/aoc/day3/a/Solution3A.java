package org.frieder.aoc.day3.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.function.Function;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.Collection;
import java.util.stream.IntStream;
import java.util.stream.Stream;

import static java.util.Map.entry;
import static java.util.stream.Collectors.flatMapping;
import static java.util.stream.Collectors.mapping;
import static java.util.stream.Collectors.teeing;
import static java.util.stream.Collectors.toMap;

public class Solution3A {

    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");
    private static final Pattern PATTERN_SYMBOLS = Pattern.compile("([^0-9.]+)");

    public static int getResult(String path) throws IOException {

        AtomicInteger numberLineNumber = new AtomicInteger();
        AtomicInteger symbolLineNumber = new AtomicInteger();

        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .collect(teeing(
                        mapping(ln -> {
                                    int currentLineNumber = numberLineNumber.getAndIncrement();
                                    return PATTERN_NUMBERS.matcher(ln)
                                            .results()
                                            .map(mr -> entry(new Pos(mr.start(1), currentLineNumber), mr.group(1)))
                                            .collect(toMap(Entry::getKey, Entry::getValue));
                                }, flatMapping(m -> m.entrySet().stream(), toMap(Entry::getKey, Entry::getValue))
                        ),
                        mapping(ln -> {
                                    int currentLineNumber = symbolLineNumber.getAndIncrement();
                                    return PATTERN_SYMBOLS.matcher(ln)
                                            .results()
                                            .map(mr -> entry(new Pos(mr.start(1), currentLineNumber), mr.group(1)))
                                            .collect(toMap(Entry::getKey, Entry::getValue));
                                }, flatMapping(m -> m.entrySet().stream(), toMap(Entry::getKey, Entry::getValue))
                        ),
                        (numbers, symbols) -> numbers.entrySet().stream()
                                .map(ne -> IntStream.range(
                                                ne.getKey().getX() - 1,
                                                ne.getKey().getX() + ne.getValue().length() + 1)
                                        .filter(x -> IntStream.range(
                                                        ne.getKey().getY() - 1,
                                                        ne.getKey().getY() + 2)
                                                .anyMatch(y -> symbols.entrySet()
                                                        .stream()
                                                        .anyMatch(se -> se.getKey().equals(new Pos(x, y)))
                                                )
                                        )
                                        .map(f -> Integer.parseInt(ne.getValue()))
                                        .findFirst()
                                        .orElse(0)
                                )
                                .mapToInt(Integer::intValue)
                                .sum()
                ));
    }
}