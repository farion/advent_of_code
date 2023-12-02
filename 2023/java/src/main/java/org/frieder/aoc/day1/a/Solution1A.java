package org.frieder.aoc.day1.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.regex.MatchResult;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class Solution1A {

    private static final Pattern PATTERN = Pattern.compile("([0-9])");

    public static int getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .map(ln -> PATTERN.matcher(ln)
                        .results()
                        .map(MatchResult::group)
                        .map(Integer::valueOf)
                        .collect(Collectors.toList())
                )
                .map(lst -> lst.get(0) * 10 + lst.get(lst.size() - 1))
                .mapToInt(Integer::intValue)
                .sum();
    }
}
