package org.frieder.aoc.day4;

import org.frieder.aoc.day4.a.Solution4A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay4A
{
    @Test
    public void testExample() throws IOException {
        assertEquals(13, Solution4A.getResult("../resources/4/test_a.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(23235, Solution4A.getResult("../resources/4/input.txt"));
    }
}
