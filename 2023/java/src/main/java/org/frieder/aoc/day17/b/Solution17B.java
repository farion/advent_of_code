package org.frieder.aoc.day17.b;

import org.frieder.aoc.day17.lib.Solver;

import java.io.IOException;

public class Solution17B {

    public static double getResult(String path) throws IOException {
        return new Solver().getResult(path,4,10);
    }
}