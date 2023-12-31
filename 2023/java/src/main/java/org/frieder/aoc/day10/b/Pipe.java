package org.frieder.aoc.day10.b;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.Arrays;

@AllArgsConstructor
@Getter
public enum Pipe {

    DR("F"), DL("7"), UR("L"), UL("J"),
    DU("|"), LR("-"),
    __("."),
    O("O"), I("I"),
    S("S");

    private final String str;

    public static String d(Pipe p, Direction d) {
        return p.name() + "-" + d.name();
    }

    public static Pipe byStr(String str) {
        return Arrays.stream(values())
                .filter(p -> p.getStr().equals(str))
                .findFirst()
                .orElse(null);
    }
}
