package org.frieder.aoc.day3.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.HashMap;;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.stream.IntStream;
import java.util.stream.Stream;

import static java.util.Map.entry;
import static java.util.stream.Collectors.flatMapping;
import static java.util.stream.Collectors.mapping;
import static java.util.stream.Collectors.teeing;
import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;
import static java.util.stream.Collectors.toSet;
import static java.util.stream.IntStream.range;
import static java.util.stream.Stream.of;

public class Solution3B {

    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");
    private static final Pattern PATTERN_GEARS = Pattern.compile("([*]+)");

    public static int getResult(String path) throws IOException {


        AtomicInteger numberLineNumber = new AtomicInteger();
        AtomicInteger gearLineNumber = new AtomicInteger();

        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .collect(teeing(
                        mapping(ln -> {
                                    int currentLineNumber = numberLineNumber.getAndIncrement();
                                    return PATTERN_NUMBERS.matcher(ln)
                                            .results()
                                            .map(mr -> entry(new Pos(mr.start(1), currentLineNumber), mr.group(1)))
                                            .collect(toMap(Map.Entry::getKey, Map.Entry::getValue));
                                }, flatMapping(m -> m.entrySet().stream(), toMap(Map.Entry::getKey, Map.Entry::getValue))
                        ),
                        mapping(ln -> {
                                    int currentLineNumber = gearLineNumber.getAndIncrement();
                                    return PATTERN_GEARS.matcher(ln)
                                            .results()
                                            .map(mr -> entry(new Pos(mr.start(1), currentLineNumber), mr.group(1)))
                                            .collect(toMap(Map.Entry::getKey, Map.Entry::getValue));
                                }, flatMapping(m -> m.entrySet().stream(), toMap(Map.Entry::getKey, Map.Entry::getValue))
                        ),
                        (numbers, gears) -> numbers.entrySet().stream()
                                .map(ne -> range(
                                                ne.getKey().getX() - 1,
                                                ne.getKey().getX() + ne.getValue().length() + 1)
                                        .mapToObj(x -> range(
                                                        ne.getKey().getY() - 1,
                                                        ne.getKey().getY() + 2)
                                                .mapToObj(y -> gears.keySet()
                                                        .stream()
                                                        .filter(gp -> gp.equals(new Pos(x, y)))
                                                        .collect(toList())
                                                )
                                                .flatMap(Collection::stream)
                                                .collect(toList())
                                        )
                                        .flatMap(Collection::stream)
                                        .map(p -> new Gear(p, Set.of(Integer.parseInt(ne.getValue()))))
                                        .collect(toList())

                                )
                                .flatMap(Collection::stream)
                                .collect(toMap(Gear::getPos,
                                                Gear::getNumbers,
                                                (a, b) -> of(a, b)
                                                        .flatMap(Set::stream)
                                                        .collect(toSet()),
                                                HashMap::new
                                        )
                                )
                                .values().stream()
                                .filter(n -> n.size() == 2)
                                .map(n -> n.stream().reduce(1, (a, b) -> a * b))
                                .mapToInt(Integer::intValue)
                                .sum()
                ));
    }
}