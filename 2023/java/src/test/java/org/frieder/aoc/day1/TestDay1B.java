package org.frieder.aoc.day1;

import org.frieder.aoc.day1.b.Solution1B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay1B
{

    @Test
    public void testExample() throws IOException {
        assertEquals(281, Solution1B.getResult("../resources/1/test_b.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(56017, Solution1B.getResult("../resources/1/input.txt"));
    }
}
