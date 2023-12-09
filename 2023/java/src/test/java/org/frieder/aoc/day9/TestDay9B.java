package org.frieder.aoc.day9;

import org.frieder.aoc.day9.b.Solution9B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay9B {
    @Test
    public void testExample() throws IOException {
        assertEquals(2, Solution9B.getResult("../resources/9/test_b.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(928, Solution9B.getResult("../resources/9/input.txt"), 0);
    }
}
