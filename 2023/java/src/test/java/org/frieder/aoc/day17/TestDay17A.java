package org.frieder.aoc.day17;

import org.frieder.aoc.day17.a.Solution17A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay17A {

    @Test
    public void testExample() throws IOException {
        assertEquals(102, Solution17A.getResult("../resources/17/test_a.txt"), 0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(1263, Solution17A.getResult("../resources/17/input.txt"), 0);
    }
}
