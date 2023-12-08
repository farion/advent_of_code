package org.frieder.aoc.day8;

import org.frieder.aoc.day8.b.Solution8B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay8B {
    @Test
    public void testExample() throws IOException {
        assertEquals(6D, Solution8B.getResult("../resources/8/test_b.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(14299763833181D, Solution8B.getResult("../resources/8/input.txt"), 0);
    }
}
