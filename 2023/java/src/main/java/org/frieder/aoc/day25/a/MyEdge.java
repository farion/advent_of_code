package org.frieder.aoc.day25.a;

import org.jgrapht.Graph;
import org.jgrapht.graph.DefaultEdge;

public class MyEdge extends DefaultEdge {

    void addToGraph(Graph<String, MyEdge> g) {
        g.addVertex((String) this.getSource());
        g.addVertex((String) this.getTarget());
        g.addEdge((String) this.getSource(), (String) this.getTarget());
    }

    @Override
    public boolean equals(Object o){
        if(o instanceof MyEdge){
            return ((MyEdge) o).getSource().equals(this.getSource()) && ((MyEdge) o).getTarget().equals(this.getTarget());
        }
        throw new RuntimeException();
    }

    @Override
    public int hashCode() {
        return this.getSource().hashCode()*this.getTarget().hashCode();
    }
}
