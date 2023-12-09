package org.frieder.aoc.day9;

import org.frieder.aoc.day9.a.Solution9A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay9A {
    @Test
    public void testExample() throws IOException {
        assertEquals(114, Solution9A.getResult("../resources/9/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(1974232246, Solution9A.getResult("../resources/9/input.txt"), 0);
    }
}
