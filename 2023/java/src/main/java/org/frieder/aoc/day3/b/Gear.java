package org.frieder.aoc.day3.b;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.Set;

@AllArgsConstructor
@Getter
public class Gear {
    private Pos pos;

    private Set<Integer> numbers;
}
