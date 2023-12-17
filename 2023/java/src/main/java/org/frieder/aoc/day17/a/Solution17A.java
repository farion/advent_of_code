package org.frieder.aoc.day17.a;

import org.frieder.aoc.day17.lib.Solver;

import java.io.IOException;

public class Solution17A {

    public static double getResult(String path) throws IOException {
        return new Solver().getResult(path,1,3);
    }
}