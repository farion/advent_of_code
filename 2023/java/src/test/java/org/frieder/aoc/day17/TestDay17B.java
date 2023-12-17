package org.frieder.aoc.day17;

import org.frieder.aoc.day17.b.Solution17B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay17B {

    @Test
    public void testExample1() throws IOException {
        assertEquals(94, Solution17B.getResult("../resources/17/test_a.txt"), 0);
    }

    @Test
    public void testExample2() throws IOException {
        assertEquals(71, Solution17B.getResult("../resources/17/test_b.txt"), 0);
    }


    @Test
    public void testResult() throws IOException {
        assertEquals(1411, Solution17B.getResult("../resources/17/input.txt"), 0);
    }
}
