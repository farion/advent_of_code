package org.frieder.aoc.day4;

import org.frieder.aoc.day4.b.Solution4B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay4B {
    @Test
    public void testExample() throws IOException {
        assertEquals(30, Solution4B.getResult("../resources/4/test_b.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(5920640, Solution4B.getResult("../resources/4/input.txt"));
    }
}
