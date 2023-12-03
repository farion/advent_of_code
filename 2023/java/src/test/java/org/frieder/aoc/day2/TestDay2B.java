package org.frieder.aoc.day2;

import static org.junit.Assert.assertEquals;

import org.frieder.aoc.day2.b.Solution2B;
import org.junit.Test;

import java.io.IOException;

public class TestDay2B {
    @Test
    public void testExample() throws IOException {
        assertEquals(2286, Solution2B.getResult("../resources/2/test_b.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(68638, Solution2B.getResult("../resources/2/input.txt"));
    }
}
