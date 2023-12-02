package org.frieder.aoc;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

import org.frieder.aoc.a.SolutionA;
import org.frieder.aoc.b.SolutionB;
import org.junit.Test;

import java.io.IOException;

public class AppTest 
{
    @Test
    public void testA() throws IOException {
        assertEquals(142, SolutionA.getResult("../test_a.txt"));
    }

    @Test
    public void testB() throws IOException {
        assertEquals(281, SolutionB.getResult("../test_b.txt"));
    }

    @Test
    public void resultA() throws IOException {
        assertEquals(56506, SolutionA.getResult("../input.txt"));
    }

    @Test
    public void resultB() throws IOException {
        assertEquals(56017, SolutionB.getResult("../input.txt"));
    }
}
