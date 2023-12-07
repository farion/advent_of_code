package org.frieder.aoc.day7;

import org.frieder.aoc.day7.a.Solution7A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay7A {
    @Test
    public void testExample() throws IOException {
        assertEquals(6440, Solution7A.getResult("../resources/7/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(251029473, Solution7A.getResult("../resources/7/input.txt"), 0);
    }
}
