package org.frieder.aoc.day10;

import org.frieder.aoc.day10.a.Solution10A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay10A {

    @Test
    public void testExample1() throws IOException {
        assertEquals(4, Solution10A.getResult("../resources/10/test_a0.txt"), 0);
    }

    @Test
    public void testExample2() throws IOException {
        assertEquals(8, Solution10A.getResult("../resources/10/test_a1.txt"), 0);
    }


    @Test
    public void testResult() throws IOException {
        assertEquals(6820, Solution10A.getResult("../resources/10/input.txt"), 0);
    }
}
