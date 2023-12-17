package org.frieder.aoc.day17.lib;

import lombok.AllArgsConstructor;
import lombok.EqualsAndHashCode;
import lombok.Getter;

@AllArgsConstructor
@Getter
@EqualsAndHashCode
public class Coordinate {

    private int x;

    private int y;

    @Override
    public String toString() {
        return x + ":" + y;
    }

}
