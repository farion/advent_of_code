package org.frieder.aoc.day7;

import org.frieder.aoc.day7.b.Solution7B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay7B {
    @Test
    public void testExample() throws IOException {
        assertEquals(5905, Solution7B.getResult("../resources/7/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(251003917, Solution7B.getResult("../resources/7/input.txt"), 0);
    }
}
