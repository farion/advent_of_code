package org.frieder.aoc.day10.a;

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

    public static Pipe byStr(String str) {
        return Arrays.stream(values())
                .filter(p -> p.getStr().equals(str))
                .findFirst()
                .orElse(null);
    }

    @Override
    public String toString(){
        return this.str;
    }
}
