package org.frieder.aoc.day6;

import org.frieder.aoc.day6.a.Solution6A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay6A {
    @Test
    public void testExample() throws IOException {
        assertEquals(288, Solution6A.getResult("../resources/6/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(800280, Solution6A.getResult("../resources/6/input.txt"), 0);
    }
}
