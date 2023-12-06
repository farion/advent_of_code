package org.frieder.aoc.day6;

import org.frieder.aoc.day6.b.Solution6B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay6B {
    @Test
    public void testExample() throws IOException {
        assertEquals(71503, Solution6B.getResult("../resources/6/test_b.txt"),0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(45128024, Solution6B.getResult("../resources/6/input.txt"),0);
    }
}
