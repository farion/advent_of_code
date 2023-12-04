package org.frieder.aoc.day4.a;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.List;

@AllArgsConstructor
@Getter
public class Card {
    private List<Integer> winning;
    private List<Integer> having;
}
