package org.frieder.aoc.day3;

import org.frieder.aoc.day3.b.Solution3B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay3B {
    @Test
    public void testExample() throws IOException {
        assertEquals(467835, Solution3B.getResult("../resources/3/test_b.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(78826761, Solution3B.getResult("../resources/3/input.txt"));
    }
}
