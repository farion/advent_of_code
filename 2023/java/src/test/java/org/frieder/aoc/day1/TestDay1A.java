package org.frieder.aoc.day1;

import org.frieder.aoc.day1.a.Solution1A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay1A
{
    @Test
    public void testExample() throws IOException {
        assertEquals(142, Solution1A.getResult("../resources/1/test_a.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(56506, Solution1A.getResult("../resources/1/input.txt"));
    }
}
