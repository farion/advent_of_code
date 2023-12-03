package org.frieder.aoc.day2.a;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.Map;

@Getter
@AllArgsConstructor
public class Game {

    private Integer number;

    private Map<String, Integer> cubes;
}
