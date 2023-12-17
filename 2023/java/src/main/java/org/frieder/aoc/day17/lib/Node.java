package org.frieder.aoc.day17.lib;

import lombok.AllArgsConstructor;
import lombok.EqualsAndHashCode;
import lombok.Getter;

@AllArgsConstructor
@Getter
@EqualsAndHashCode
public class Node {

    private Coordinate c;

    private Direction d;

    @Override
    public String toString() {
        return c.getX() + ":" + c.getY() + d.toString();
    }

}
