#include "dSFMT.h"

#define RANDOM_VARIABLE_TYPE dsfmt_t  // the type of random variable for a random generator
#define BASIC_RANDOM_VALUE dsfmt_genrand_close_open
#define GAUSS_RANDOM_VALUE dSFMT_NormRandGenerator
#define POISSON_RANDOM_VALUE dSFMT_PoissonRandValue

// Initialize generator with a seed)
#define INIT_RANDOM_GENERATOR(GRV,seed) (dsfmt_init_gen_rand(&GRV, seed))

/*
#include "Randoms.h"

#define RANDOM_VARIABLE_TYPE int  // the type of random variable for a random generator
#define BASIC_RANDOM_VALUE MLCG_2147483647_16807
#define GAUSS_RANDOM_VALUE NormRandG

// Initialize generator with a seed)
#define INIT_RANDOM_GENERATOR(GRV,seed) (GRV=seed)            */
