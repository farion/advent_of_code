package org.frieder.aoc.day8;

import org.frieder.aoc.day8.a.Solution8A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay8A {
    @Test
    public void testExample() throws IOException {
        assertEquals(2, Solution8A.getResult("../resources/8/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(18157, Solution8A.getResult("../resources/8/input.txt"), 0);
    }
}
