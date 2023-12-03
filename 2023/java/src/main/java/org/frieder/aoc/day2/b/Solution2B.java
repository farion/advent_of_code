package org.frieder.aoc.day2.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class Solution2B {

    private static final Pattern PATTERN_GAME = Pattern.compile("^Game ([0-9]+): (.*)$");
    private static final Pattern PATTERN_CUBE = Pattern.compile("(([0-9]+) ([a-z]+))");

    public static int getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .flatMap(ln -> PATTERN_GAME.matcher(ln)
                        .results()
                        .map(mr -> new Game(Integer.parseInt(mr.group(1)), PATTERN_CUBE.matcher(mr.group(2))
                                .results()
                                .map(cmr -> Map.entry(cmr.group(3), Integer.parseInt(cmr.group(2))))
                                .collect(Collectors.toMap(
                                        Map.Entry::getKey,
                                        Map.Entry::getValue,
                                        Math::max,
                                        HashMap::new
                                ))))
                )
                .map(g -> g.getCubes()
                        .values()
                        .stream()
                        .mapToInt(Integer::intValue)
                        .reduce(1, (a, b) -> a * b)
                )
                .mapToInt(Integer::intValue)
                .sum();
    }
}