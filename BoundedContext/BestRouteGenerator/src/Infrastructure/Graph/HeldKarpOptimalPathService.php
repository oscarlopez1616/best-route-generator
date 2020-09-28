<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure\Graph;


class HeldKarpOptimalPathService
{


    public function heldKarp($matrix) {
        $nb_nodes = count($matrix);

        # Maps each subset of the nodes to the cost to reach that subset, as well
        # as what node it passed before reaching this subset.
        # Node subsets are represented as set bits.
        $c = [];

        # Set transition cost from initial state
        for($k = 1; $k < $nb_nodes; $k++) {
            $c["(" . (1 << $k) . ",$k)"] = [$matrix[0][$k], 0];
        }

        # Iterate subsets of increasing length and store intermediate results
        # in classic dynamic programming manner
        for($subset_size = 2; $subset_size < $nb_nodes; $subset_size++) {
            $combinaisons = $this->everyCombinations(range(1, $nb_nodes - 1), $subset_size, false);
            foreach($combinaisons AS $subset) {
                # Set bits for all nodes in this subset
                $bits = 0;
                foreach($subset AS $bit) {
                    $bits |= 1 << $bit;
                }

                # Find the lowest cost to get to this subset
                foreach($subset AS $bk) {
                    $prev = $bits & ~(1 << $bk);

                    $res = [];
                    foreach($subset AS $m) {
                        if(($m === 0)||($m === $bk)) {
                            continue;
                        }
                        $res[] = [$c["($prev,$m)"][0] + $matrix[$m][$bk], $m];
                    }
                    $c["($bits,$bk)"] = min($res);
                }
            }
        }

        # We're interested in all bits but the least significant (the start state)
        $bits = (2**$nb_nodes - 1) - 1;

        # Calculate optimal cost
        $res = [];
        for($k = 1; $k < $nb_nodes; $k++) {
            $res[] = [$c["($bits,$k)"][0] + $matrix[$k][0], $k];
        }
        [$opt, $parent] = min($res);

        # Backtrack to find full path
        $path = [];
        for($i = 0; $i < $nb_nodes - 1; $i++) {
            $path[] = $parent;
            $new_bits = $bits & ~(1 << $parent);
            list($scrap, $parent) = $c["($bits,$parent)"];
            $bits = $new_bits;
        }

        # Add implicit start state
        $path[] = 0;

        return [$opt, array_reverse($path)];
    }

    private function everyCombinations($set, $n = NULL, $order_matters = true) {
        if($n === NULL) {
            $n = count($set);
        }
        $combinations = [];
        foreach($set AS $k => $e) {
            $subset = $set;
            unset($subset[$k]);
            if($n === 1) {
                $combinations[] = [$e];
            }
            else {
                $subcomb = $this->everyCombinations($subset, $n - 1, $order_matters);
                foreach($subcomb AS $s) {
                    $comb = array_merge([$e], $s);
                    if($order_matters) {
                        $combinations[] = $comb;
                    }
                    else {
                        $needle = $comb;
                        sort($needle);
                        if(!in_array($needle, $combinations, true)) $combinations[] = $comb;
                    }
                }
            }
        }
        return $combinations;
    }

}
