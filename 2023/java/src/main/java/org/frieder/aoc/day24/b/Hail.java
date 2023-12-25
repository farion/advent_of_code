package org.frieder.aoc.day24.b;

import lombok.AllArgsConstructor;
import lombok.Getter;

@AllArgsConstructor
@Getter
public class Hail {

    private long x;
    private long y;
    private long z;
    private long vx;
    private long vy;
    private long vz;

    @Override
    public String toString() {
        return x + " " + y + " " + z + " -> " + vx + " " + vy + " " + vz;
    }
}
