package org.frieder.aoc.day24.b;

import com.microsoft.z3.BoolExpr;
import com.microsoft.z3.Context;
import com.microsoft.z3.Expr;
import com.microsoft.z3.IntExpr;
import com.microsoft.z3.Model;
import com.microsoft.z3.Solver;
import org.frieder.aoc.day10.b.Coordinate;
import org.frieder.aoc.day10.b.Direction;
import org.frieder.aoc.day10.b.Field;
import org.frieder.aoc.day10.b.Pipe;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collection;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicReference;
import java.util.function.Function;
import java.util.regex.Pattern;
import java.util.stream.IntStream;

import static java.util.stream.Collectors.toList;
import static java.util.stream.Collectors.toMap;
import static org.frieder.aoc.day10.b.Direction.DOWN;
import static org.frieder.aoc.day10.b.Direction.LEFT;
import static org.frieder.aoc.day10.b.Direction.RIGHT;
import static org.frieder.aoc.day10.b.Direction.UNDEFINED;
import static org.frieder.aoc.day10.b.Direction.UP;
import static org.frieder.aoc.day10.b.Pipe.I;
import static org.frieder.aoc.day10.b.Pipe.O;
import static org.frieder.aoc.day10.b.Pipe.__;

public class Solution24B {

    private final Logger LOGGER = LoggerFactory.getLogger(Solution24B.class);

    private static final Pattern PATTERN_HAIL = Pattern.compile("^ *(-?[0-9]+), +(-?[0-9]+), +(-?[0-9]+) +@ +(-?[0-9]+), +(-?[0-9]+), +(-?[0-9]+) *$");

    public static double getResult(String path) throws IOException {
        return new Solution24B().getNonStaticResult(path);
    }

    private double getNonStaticResult(String path) throws IOException {
        List<Hail> hails = Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> !ln.trim().isEmpty())
                .map(ln -> PATTERN_HAIL.matcher(ln)
                        .results()
                        .map(mr -> new Hail(Long.parseLong(mr.group(1)),
                                        Long.parseLong(mr.group(2)),
                                        Long.parseLong(mr.group(3)),
                                        Long.parseLong(mr.group(4)),
                                        Long.parseLong(mr.group(5)),
                                        Long.parseLong(mr.group(6))
                                )
                        )
                        .collect(toList())
                )
                .flatMap(Collection::stream)
                .collect(toList());

        /*
         * Rock = r
         * Hail = a
         * Hail = b
         * Hail = c
         * Hail = d
         *
         * rx + ta * vrx = ax + ta * vax
         * ry + ta * vry = ay + ta * vay
         * rz + ta * vrz = az + ta * vaz
         *
         * rx + tb * vrx = bx + tb * vbx
         * ry + tb * vry = by + tb * vby
         * rz + tb * vrz = bz + tb * vbz
         *
         * rx + tc * vrx = cx + tc * vcx
         * ry + tc * vry = cy + tc * vcy
         * rz + tc * vrz = cz + tc * vcz
         *
         * rx + td * vrx = cx + tc * vdx
         * ry + td * vry = cy + tc * vdy
         * rz + td * vrz = cz + tc * vdz
         */

        try (Context ctx = new Context()) {
            Solver solver = ctx.mkSolver();
            IntExpr rx = ctx.mkIntConst("rx");
            IntExpr ry = ctx.mkIntConst("ry");
            IntExpr rz = ctx.mkIntConst("rz");
            IntExpr vrx = ctx.mkIntConst("vrx");
            IntExpr vry = ctx.mkIntConst("vry");
            IntExpr vrz = ctx.mkIntConst("vrz");
            IntExpr zero = ctx.mkInt(0);

            for (int i = 0; i < 5; i++) {

                IntExpr t = ctx.mkIntConst("t" + i);
                Hail hail = hails.get(i);

                IntExpr ax = ctx.mkInt(hail.getX());
                IntExpr ay = ctx.mkInt(hail.getY());
                IntExpr az = ctx.mkInt(hail.getZ());
                IntExpr vax = ctx.mkInt(hail.getVx());
                IntExpr vay = ctx.mkInt(hail.getVy());
                IntExpr vaz = ctx.mkInt(hail.getVz());

                BoolExpr eqX = ctx.mkEq(ctx.mkAdd(rx, ctx.mkMul(t, vrx)), ctx.mkAdd(ax, ctx.mkMul(t, vax)));
                BoolExpr eqY = ctx.mkEq(ctx.mkAdd(ry, ctx.mkMul(t, vry)), ctx.mkAdd(ay, ctx.mkMul(t, vay)));
                BoolExpr eqZ = ctx.mkEq(ctx.mkAdd(rz, ctx.mkMul(t, vrz)), ctx.mkAdd(az, ctx.mkMul(t, vaz)));

                solver.add(eqX);
                solver.add(eqY);
                solver.add(eqZ);
                solver.add(ctx.mkGt(t, zero));

            }
            solver.check();
            Model model = solver.getModel();

            return Long.parseLong(model.eval(rx, false).toString()) +
                    Long.parseLong(model.eval(ry, false).toString()) +
                    Long.parseLong(model.eval(rz, false).toString());
        }
    }
}