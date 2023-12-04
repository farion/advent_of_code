package org.frieder.aoc.day3;

import org.frieder.aoc.day3.a.Solution3A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay3A
{
    @Test
    public void testExample() throws IOException {
        assertEquals(4361, Solution3A.getResult("../resources/3/test_a.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(533784, Solution3A.getResult("../resources/3/input.txt"));
    }
}
