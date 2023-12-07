package org.frieder.aoc.day7.a;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.HashMap;
import java.util.Map;
import java.util.function.Function;
import java.util.stream.Collectors;
import java.util.stream.IntStream;
import java.util.stream.Stream;

import static org.frieder.aoc.day7.a.Card.CardValue.FIVE_OF_A_KIND;
import static org.frieder.aoc.day7.a.Card.CardValue.FOUR_OF_A_KIND;
import static org.frieder.aoc.day7.a.Card.CardValue.FULL_HOUSE;
import static org.frieder.aoc.day7.a.Card.CardValue.HIGH_CARD;
import static org.frieder.aoc.day7.a.Card.CardValue.ONE_PAIR;
import static org.frieder.aoc.day7.a.Card.CardValue.THREE_OF_A_KIND;
import static org.frieder.aoc.day7.a.Card.CardValue.TWO_PAIR;

@AllArgsConstructor
@Getter
public class Card implements Comparable<Card> {

    enum CardValue {
        HIGH_CARD, ONE_PAIR, TWO_PAIR, THREE_OF_A_KIND, FULL_HOUSE, FOUR_OF_A_KIND, FIVE_OF_A_KIND
    }

    private String hand;

    private Integer bid;

    private static final Map<Character, Character> CHAR_MAPPING = new HashMap<>() {{
        put('A', 'E');
        put('K', 'D');
        put('Q', 'C');
        put('J', 'B');
        put('T', 'A');
    }};

    private static final Map<Integer, Function<Map<Character, Long>, CardValue>> TYPE_DETECTORS = new HashMap<>() {{
        put(1, m -> FIVE_OF_A_KIND);
        put(2, m -> m.containsValue(4L) ? FOUR_OF_A_KIND : FULL_HOUSE);
        put(3, m -> m.containsValue(3L) ? THREE_OF_A_KIND : TWO_PAIR);
        put(4, m -> ONE_PAIR);
        put(5, m -> HIGH_CARD);
    }};

    private CardValue getCardValue() {
        return Stream.of(IntStream.range(0, hand.length())
                        .mapToObj(hand::charAt)
                        .collect(Collectors.groupingBy(Function.identity(), Collectors.counting())))
                .map(counts -> TYPE_DETECTORS.get(counts.size()).apply(counts))
                .findFirst()
                .orElseThrow();
    }

    private String getHexHand() {
        return hand.chars()
                .map(ch -> CHAR_MAPPING.containsKey((char) ch) ? CHAR_MAPPING.get((char) ch) : ch)
                .collect(StringBuilder::new, (builder, c) -> builder.append((char) c), StringBuilder::append)
                .toString();
    }

    private int compareHand(Card other) {
        String h1 = this.getHexHand();
        String h2 = other.getHexHand();
        return IntStream.range(0, 5)
                .filter(i -> h1.charAt(i) != h2.charAt(i))
                .map(i -> h1.charAt(i) > h2.charAt(i) ? 1 : -1)
                .findFirst()
                .orElse(0);
    }

    @Override
    public int compareTo(Card other) {
        return Stream.of(getCardValue().compareTo(other.getCardValue()))
                .map(r -> r != 0 ? r : compareHand(other))
                .findFirst()
                .orElse(0);
    }
}
