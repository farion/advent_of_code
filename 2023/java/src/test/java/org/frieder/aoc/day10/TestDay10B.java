package org.frieder.aoc.day10;

import org.frieder.aoc.day10.b.Solution10B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay10B {
    @Test
    public void testExample() throws IOException {
        assertEquals(10, Solution10B.getResult("../resources/10/test_b.txt"), 0);
    }


    @Test
    public void testResult() throws IOException {
        assertEquals(337, Solution10B.getResult("../resources/10/input.txt"), 0);
    }
}
