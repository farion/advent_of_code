package org.frieder.aoc.day1.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.Comparator;
import java.util.List;
import java.util.stream.Collectors;

public class Solution1B {

    private static final List<SearchWord> SEARCH_WORDS = SearchWord.fromArray(new String[][]{
            {"1", "one"},
            {"2", "two"},
            {"3", "three"},
            {"4", "four"},
            {"5", "five"},
            {"6", "six"},
            {"7", "seven"},
            {"8", "eight"},
            {"9", "nine"},
            {"1"},
            {"2"},
            {"3"},
            {"4"},
            {"5"},
            {"6"},
            {"7"},
            {"8"},
            {"9"}
    });

    public static int getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .map(ln -> SEARCH_WORDS.stream()
                        .map(n -> n.getPattern()
                                .matcher(ln)
                                .results()
                                .map(m -> new Finding(m.start(), n.getResult()))
                                .collect(Collectors.toList())
                        )
                        .flatMap(Collection::stream)
                        .sorted(Comparator.comparingInt(Finding::getPosition))
                        .collect(Collectors.toList()))
                .map(fnd -> fnd.get(0).getValue() * 10 + fnd.get(fnd.size() - 1).getValue())
                .mapToInt(Integer::intValue)
                .sum();
    }
}


