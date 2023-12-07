package org.frieder.aoc.day7.a;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.Map;
import java.util.function.Function;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

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

    private String hand;

    private Integer bid;

    enum CardValue {
        HIGH_CARD,
        ONE_PAIR,
        TWO_PAIR,
        THREE_OF_A_KIND,
        FULL_HOUSE,
        FOUR_OF_A_KIND,
        FIVE_OF_A_KIND
    }

    private CardValue getCardValue() {
        Map<Character, Long> counts = IntStream.range(0, hand.length())
                .mapToObj(hand::charAt)
                .collect(Collectors.groupingBy(Function.identity(), Collectors.counting()));

        switch (counts.size()) {
            case 1:
                return FIVE_OF_A_KIND;
            case 2:
                return counts.containsValue(4L) ? FOUR_OF_A_KIND : FULL_HOUSE;
            case 3:
                return counts.containsValue(3L) ? THREE_OF_A_KIND : TWO_PAIR;
            case 4:
                return ONE_PAIR;
            case 5:
                return HIGH_CARD;
        }

        throw new RuntimeException();
    }

    private String getHexHand() {
        return hand.replaceAll("A", "E")
                .replaceAll("K", "D")
                .replaceAll("Q", "C")
                .replaceAll("J", "B")
                .replaceAll("T", "A");
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
        CardValue v1 = getCardValue();
        CardValue v2 = other.getCardValue();

        if (v1.compareTo(v2) < 0)
            return -1;

        if (v1.compareTo(v2) > 0)
            return 1;

        return compareHand(other);
    }
}
