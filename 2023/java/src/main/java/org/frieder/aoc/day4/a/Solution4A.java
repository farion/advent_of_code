package org.frieder.aoc.day4.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class Solution4A {

    private static final Pattern PATTERN_CARD = Pattern.compile("Card +[0-9]+: ([0-9 ]+) \\| ([0-9 ]+)");
    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");

    public static int getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .map(Solution4A::lineToCard)
                .map(Solution4A::cardMatchCount)
                .map(cc -> Math.floor(Math.pow(2, cc - 1)))
                .mapToInt(Double::intValue)
                .sum();
    }

    private static Card lineToCard(String ln) {
        return PATTERN_CARD.matcher(ln)
                .results()
                .map(mr -> new Card(
                        parseNumbers(mr.group(1)),
                        parseNumbers(mr.group(2))
                ))
                .findFirst()
                .orElse(null);
    }

    private static float cardMatchCount(Card c) {
        return c.getHaving().stream()
                .filter(h -> c.getWinning().contains(h))
                .count();
    }

    private static List<Integer> parseNumbers(String str) {
        return PATTERN_NUMBERS.matcher(str)
                .results()
                .map(mrW -> mrW.group(1))
                .map(Integer::parseInt)
                .collect(Collectors.toList());
    }
}