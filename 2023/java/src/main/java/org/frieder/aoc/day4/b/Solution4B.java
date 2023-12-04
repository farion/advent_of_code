package org.frieder.aoc.day4.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.Map;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

import static java.util.stream.Collectors.collectingAndThen;
import static java.util.stream.IntStream.concat;
import static java.util.stream.IntStream.of;
import static java.util.stream.IntStream.range;

public class Solution4B {

    private static final Pattern PATTERN_CARD = Pattern.compile("Card +([0-9]+): ([0-9 ]+) \\| ([0-9 ]+)");
    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");

    public static int getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.length() > 0)
                .map(Solution4B::lineToCard)
                .collect(collectingAndThen(
                        Collectors.toMap(Card::getCardNumber, c -> c, (c1, c2) -> c1),
                        cards -> cards.values().stream()
                                .map(c -> processCard(c, cards))
                ))
                .mapToInt(Integer::intValue)
                .sum();
    }

    private static int processCard(Card c, Map<Integer, Card> cards) {
        return concat(range(c.getCardNumber() + 1, c.getCardNumber() + c.getMatchCount() + 1)
                .map(i -> processCard(cards.get(i), cards)), of(1))
                .sum();
    }

    private static Card lineToCard(String ln) {
        return PATTERN_CARD.matcher(ln)
                .results()
                .map(mr -> new Card(
                        parseNumbers(mr.group(1)).get(0),
                        cardMatchCount(parseNumbers(mr.group(2)), parseNumbers(mr.group(3)))
                ))
                .findFirst()
                .orElse(null);
    }

    private static int cardMatchCount(List<Integer> winning, List<Integer> having) {
        return (int) having.stream()
                .filter(winning::contains)
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