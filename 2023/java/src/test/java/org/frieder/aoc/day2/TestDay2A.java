package org.frieder.aoc.day2;

import org.frieder.aoc.day2.a.Solution2A;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay2A
{
    @Test
    public void testExample() throws IOException {
        assertEquals(8, Solution2A.getResult("../resources/2/test_a.txt"));
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(2776, Solution2A.getResult("../resources/2/input.txt"));
    }
}
