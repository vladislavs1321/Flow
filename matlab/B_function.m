function y=B_function(x,y,z,w0,z0)
a=-2/(w0*w0);
b=-2/(z0*z0);

    y=exp( a.*(x.*x + y.*y) + b.*z.*z );
