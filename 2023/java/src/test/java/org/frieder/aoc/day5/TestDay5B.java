package org.frieder.aoc.day5;

import org.frieder.aoc.day5.b.Solution5B;
import org.junit.Test;

import java.io.IOException;

import static org.junit.Assert.assertEquals;

public class TestDay5B {
    @Test
    public void testExample() throws IOException {
        assertEquals(46D, Solution5B.getResult("../resources/5/test_b.txt"),0);
    }

    @Test
    public void testResult() throws IOException {
        assertEquals(125742456D, Solution5B.getResult("../resources/5/input.txt"),0);
    }
}
