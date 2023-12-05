package org.frieder.aoc.day5.b;

import lombok.RequiredArgsConstructor;

import java.util.Map;
import java.util.concurrent.atomic.AtomicReference;

import static org.frieder.aoc.day5.b.Solution5B.SEEK;

@RequiredArgsConstructor
public class ProcessRangeTask {

    private Double currentLocation;
    private final Seed seed;
    private final Map<String, Data> data;

    public Double getLocationNumber() {
        for (double j = 0; j < this.seed.getRange(); j++) {
            double seedNumber = this.seed.getStart() + j;
            String pathId = this.searchSeed(seedNumber);
            if (j + SEEK >= this.seed.getRange())
                continue;

            j += (pathId.equals(this.searchSeed(seedNumber + SEEK))) ? SEEK : 0;
        }

        return this.currentLocation;
    }

    private String searchSeed(Double seedNumber) {

        TargetResult r = this.getTarget("seed", "location", seedNumber, "");
        if (this.currentLocation == null) {
            this.currentLocation = r.getLocationNumber();
        } else {
            this.currentLocation = Math.min(this.currentLocation, r.getLocationNumber());
        }
        return r.getPathId();
    }

    private TargetResult getTarget(String source, String target, Double initial, String pathId) {

        if (target.equals(source))
            return new TargetResult(null, null);

        AtomicReference<String> hit = new AtomicReference<>();
        Double destination = this.data.get(source).getMappings()
                .stream()
                .filter(m -> initial >= m.getSourceStart() && initial < m.getSourceStart() + m.getRange())
                .findFirst()
                .map(m -> {
                    hit.set(m.getSourceStart() + ":" + m.getDestinationStart());
                    return m.getDestinationStart() + (initial - m.getSourceStart());
                })
                .orElse(initial);

        if (target.equals(this.data.get(source).getTarget())) {
            return new TargetResult(
                    destination,
                    pathId + source + "-to-" + this.data.get(source).getTarget() + ":" + hit.get() + ">"
            );
        }

        return this.getTarget(this.data.get(source).getTarget(), target, destination, pathId);
    }


}
