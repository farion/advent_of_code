package org.frieder.aoc.day5;

import org.frieder.aoc.day5.a.Solution5A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay5A {
    @Test
    public void testExample() throws IOException {
        assertEquals(35D, Solution5A.getResult("../resources/5/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(196167384D, Solution5A.getResult("../resources/5/input.txt"), 0);
    }
}
