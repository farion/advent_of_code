package org.frieder.aoc.day10.a;

import lombok.AllArgsConstructor;
import lombok.EqualsAndHashCode;
import lombok.Getter;

@AllArgsConstructor
@Getter
@EqualsAndHashCode
public class Coordinate {

    private int x;

    private int y;

    Coordinate up() {
        return new Coordinate(x, y - 1);
    }

    Coordinate down() {
        return new Coordinate(x, y + 1);
    }

    Coordinate left() {
        return new Coordinate(x - 1, y);
    }

    Coordinate right() {
        return new Coordinate(x + 1, y);
    }

}
